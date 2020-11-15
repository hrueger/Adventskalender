import { Component } from "@angular/core";
import { ActivatedRoute } from "@angular/router";
import { RemoteService } from "../../_services/remote.service";
import { Task } from "../../_models/Task";
import { AuthenticationService } from "../../_services/authentication.service";

@Component({
    selector: "app-task",
    templateUrl: "./task.component.html",
    styleUrls: ["./task.component.scss"],
})
export class TaskComponent {
    public task: Task;
    public loading = true;
    public cols = ["A", "B", "C", "D", "E", "F", "G", "H", "I"];
    public rows = [9, 8, 7, 6, 5, 4, 3, 2, 1];
    constructor(
        public remoteService: RemoteService,
        public authenticationService: AuthenticationService,
        private route: ActivatedRoute,
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
}
