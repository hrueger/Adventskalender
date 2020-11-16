import { Component } from "@angular/core";
import { ActivatedRoute, Router } from "@angular/router";
import { RemoteService } from "../../_services/remote.service";
import { Task } from "../../_models/Task";
import { AuthenticationService } from "../../_services/authentication.service";
import { AlertService } from "../../_services/alert.service";

@Component({
    selector: "app-task",
    templateUrl: "./task.component.html",
    styleUrls: ["./task.component.scss"],
})
export class TaskComponent {
    public task: Task;
    public loading = true;
    public selectedField: { col: string, row: number };
    public cols = ["A", "B", "C", "D", "E", "F", "G", "H", "I"];
    public rows = [9, 8, 7, 6, 5, 4, 3, 2, 1];
    constructor(
        public remoteService: RemoteService,
        public authenticationService: AuthenticationService,
        private route: ActivatedRoute,
        private alertService: AlertService,
        private router: Router,
    ) {
        this.route.params.subscribe((params) => {
            if (params.day && parseInt(params.day, 10)) {
                const day = parseInt(params.day, 10);
                this.remoteService.get(`tasks/${day}`).subscribe((t: Task) => {
                    this.task = t;
                    this.loading = false;
                });
            }
        });
    }

    public selectField(col: string, row: number): void {
        this.selectedField = {
            col,
            row,
        };
    }

    public save(): void {
        this.remoteService.post(`tasks/${this.task.day}`, { ...this.selectedField }).subscribe((d) => {
            if (d?.success) {
                this.alertService.success("Deine Lösung wurde erfolgreich gespeichert!");
                this.router.navigate(["/tasks"]);
            }
        });
    }
}
