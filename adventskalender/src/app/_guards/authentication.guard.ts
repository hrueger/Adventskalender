import { Injectable } from "@angular/core";
import {
    CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot, Router,
} from "@angular/router";
import { AuthenticationService } from "../_services/authentication.service";

@Injectable({
    providedIn: "root",
})
export class AuthenticationGuard implements CanActivate {
    constructor(
        private router: Router,
        private authenticationService: AuthenticationService,
    ) { }

    public canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): boolean {
        if (this.authenticationService.currentUser) {
            // authorised so return true
            return true;
        }

        // not logged in so redirect to login page with the return url
        this.router.navigate(["/login"], { queryParams: { returnUrl: state.url } });
        return false;
    }
}
