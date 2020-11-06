import { Component } from "@angular/core";
import { RemoteService } from "../../_services/remote.service";

@Component({
    selector: "app-tasks",
    templateUrl: "./tasks.component.html",
    styleUrls: ["./tasks.component.scss"],
})
export class TasksComponent {
    tasks: any = [];
    constructor(private remoteService: RemoteService) { }
    public ngOnInit(): void {
        this.remoteService.get("tasks").subscribe((t) => {
            this.tasks = t;
        });
    }
}
