import { Injectable } from "@angular/core";
import { Router } from "@angular/router";
import { Observable, Subject } from "rxjs";
import { map } from "rxjs/operators";
import { NoErrorHttpParams } from "../_helpers/noErrorHttpParams";
import { RemoteService } from "./remote.service";
import { StorageService } from "./storage.service";

@Injectable({
    providedIn: "root",
})
export class AuthenticationService {
    public currentUser: any;
    public onLogin = new Subject<boolean>();

    constructor(
        private remoteService: RemoteService,
        private storageService: StorageService,
        private router: Router,
    ) { }

    public login(name: string, password: string): Observable<any> {
        return this.remoteService.post("auth/login", { password, name }).pipe(
            map((user: any) => {
                // login successful if there's a jwt token in the response
                this.loggedIn(user);
                return user;
            }),
        );
    }

    public isAdmin(): boolean {
        return !!this.currentUser?.isAdmin;
    }

    private loggedIn(user: any) {
        if (user) {
            this.currentUser = user;
            this.storageService.set("jwtToken", user.jwtToken);
            this.onLogin.next(true);
        }
    }

    public autoLogin(jwtToken: string): Subject<any> {
        const o = new Subject();
        this.remoteService.post("auth/renewToken", { jwtToken }, { params: new NoErrorHttpParams(true) }).subscribe((data) => {
            if (data && data.user) {
                this.loggedIn(data.user);
                o.next(true);
            } else {
                o.next(false);
            }
        }, () => {
            o.next(false);
        });
        return o;
    }

    public logout(): void {
        this.storageService.remove("jwtToken");
        window.location.reload();
    }
}
