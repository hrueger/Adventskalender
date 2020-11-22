import { Component } from "@angular/core";
import { AuthenticationService } from "../../_services/authentication.service";

@Component({
    selector: "app-navbar",
    templateUrl: "./navbar.component.html",
    styleUrls: ["./navbar.component.scss"],
})
export class NavbarComponent {
    public showMobileMenu = false;
    constructor(public authenticationService: AuthenticationService) {}
}
