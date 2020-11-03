import { Injectable } from "@angular/core";
import { ToastrService } from "ngx-toastr";

@Injectable({ providedIn: "root" })
export class AlertService {
    private configSet = false;
    private readonly timeouts = {
        error: 999999,
        info: 20000,
        success: 5000,
        warning: 999999,
    };
    constructor(private toastr: ToastrService) {
    }

    public success(message: string): void {
        this.config();
        this.toastr.success(message, "Erfolg!", { timeOut: this.timeouts.success });
    }

    public error(message: string): void {
        this.config();
        this.toastr.error(message, "Fehler!", { timeOut: this.timeouts.error });
    }

    public info(message: string): void {
        this.config();
        this.toastr.info(message, "Info:", { timeOut: this.timeouts.info });
    }

    public warning(message: string): void {
        this.config();
        this.toastr.warning(message, "Warnung!", { timeOut: this.timeouts.warning });
    }

    public config(): void {
        if (!this.configSet) {
            this.toastr.toastrConfig.preventDuplicates = true;
            this.configSet = true;
        }
    }
}
