import { Component } from "@angular/core";
import { AuthenticationService } from "../../_services/authentication.service";

@Component({
    selector: "app-welcome",
    templateUrl: "./welcome.component.html",
    styleUrls: ["./welcome.component.scss"],
})
export class WelcomeComponent {
    constructor(public authenticationService: AuthenticationService) {}
}
