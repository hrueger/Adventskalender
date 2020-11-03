import {
    HttpErrorResponse,
    HttpEvent,
    HttpHandler,
    HttpInterceptor,
    HttpRequest,
} from "@angular/common/http";
import { Injectable } from "@angular/core";
import { Observable, throwError } from "rxjs";
import { catchError, retry } from "rxjs/operators";
import { NoErrorHttpParams } from "../_helpers/noErrorHttpParams";
import { AlertService } from "../_services/alert.service";

  @Injectable()
export class ErrorInterceptor implements HttpInterceptor {
    constructor(private toastService: AlertService) {}
    public intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
        return next.handle(request)
            .pipe(
                retry(1),
                catchError((error: HttpErrorResponse) => {
                    // eslint-disable-next-line no-console
                    console.log(error);
                    const errorMessage = error.error?.message || error.message || "unknown error!";
                    // when using { params: new NoErrorToastHttpParams(true) }, don't show toast
                    if (!(request.params instanceof NoErrorHttpParams
                        && request.params.dontShowAlert)) {
                        this.toastService.error(errorMessage);
                    }
                    return throwError(error.error);
                }),
            );
    }
}
