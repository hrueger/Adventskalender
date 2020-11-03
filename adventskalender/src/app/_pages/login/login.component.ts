import { HttpClient } from "@angular/common/http";
import { Component } from "@angular/core";
import { FormGroup, FormControl, Validators } from "@angular/forms";
import { Router, ActivatedRoute } from "@angular/router";
import { NoErrorHttpParams } from "../../_helpers/noErrorHttpParams";
import { AlertService } from "../../_services/alert.service";
import { AuthenticationService } from "../../_services/authentication.service";
import { RemoteService } from "../../_services/remote.service";
import { StorageService } from "../../_services/storage.service";

@Component({
    selector: "app-login",
    templateUrl: "./login.component.html",
    styleUrls: ["./login.component.scss"],
})
export class LoginComponent {
    public passwordLost = false;
    public loginForm: FormGroup;
    public submitted = false;
    public loading = false;
    public tryingToAutoLogin = false;
    public changePassword = false;

    private domain = "";

    constructor(
        private httpClient: HttpClient,
        private alertService: AlertService,
        private remoteService: RemoteService,
        private authenticationService: AuthenticationService,
        private router: Router,
        private storageService: StorageService,
        private route: ActivatedRoute,
    ) {
        this.loginForm = new FormGroup({
            nickname: new FormControl("", [Validators.required]),
            password: new FormControl("", [Validators.required]),
        });
        const url = typeof window !== "undefined" ? window.location.toString() : "";
        if (url.indexOf(":4200") !== -1) { // is dev
            const apiPortDev = 3000;
            const match = (url as string).match(/(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]/g);
            this.domain = `http://${match ? match[0] : "localhost"}:${apiPortDev}`;
        } else {
            this.domain = url.substring(0, url.indexOf("/login"));
        }
        this.remoteService.setApiUrl(this.domain);
        const jwtToken = this.storageService.get("jwtToken");
        const apiUrl = this.storageService.get("apiUrl");
        if (jwtToken && apiUrl) {
            this.tryingToAutoLogin = true;
            this.remoteService.setApiUrl(apiUrl);
            this.authenticationService.autoLogin(jwtToken).subscribe((success) => {
                if (success) {
                    if (this.route.snapshot.queryParams.returnUrl) {
                        this.router.navigate([this.route.snapshot.queryParams.returnUrl]);
                    } else {
                        this.router.navigate(["home"]);
                    }
                }
                this.tryingToAutoLogin = false;
            });
        }
    }

    public onSubmit(): void {
        this.alertService.removeAll();
        this.submitted = true;
        if (this.loginForm.invalid) {
            return;
        }
        this.loading = true;
        this.httpClient.get(`${this.domain}/config.json`, { params: new NoErrorHttpParams(true) }).subscribe((data: any) => {
            this.loading = false;
            if (data && data.apiUrl) {
                const apiUrl = `${this.domain}${data.apiUrl}`;
                this.storageService.set("apiUrl", apiUrl);
                this.remoteService.setApiUrl(apiUrl);
                this.authenticationService.login(
                    this.loginForm.controls.nickname.value,
                    this.loginForm.controls.password.value,
                ).subscribe((d: any) => {
                    this.submitted = false;
                    if (d.changePassword) {
                        this.changePassword = true;
                    } else {
                        this.loggedInSuccessfully();
                    }
                });
            } else {
                this.loading = false;
                this.alertService.error("Fehlerhafte config!");
            }
        }, () => {
            this.loading = false;
            this.alertService.error("Falsche Domain!");
        });
    }

    private loggedInSuccessfully() {
        this.loading = false;
        if (this.route.snapshot.queryParams.returnUrl) {
            this.router.navigate([this.route.snapshot.queryParams.returnUrl]);
        } else {
            this.router.navigate(["home"]);
        }
    }
}
