import { HttpParams } from "@angular/common/http";

export class NoErrorHttpParams extends HttpParams {
    constructor(public dontShowAlert: boolean) {
        super();
    }
}
