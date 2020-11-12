import { Component } from "@angular/core";
import { Router } from '@angular/router';
import { AuthenticationService } from 'src/app/_services/authentication.service';

@Component({
    selector: "app-home",
    templateUrl: "./home.component.html",
    styleUrls: ["./home.component.scss"],
})
export class HomeComponent {
    constructor(private authenticationService: AuthenticationService, private router: Router) {
        if (this.authenticationService.currentUser) {
            this.router.navigate(["/welcome"]);
        }
    }
}
