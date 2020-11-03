import { Component } from "@angular/core";
import { ActivatedRoute } from "@angular/router";

@Component({
    selector: "app-task",
    templateUrl: "./task.component.html",
    styleUrls: ["./task.component.scss"],
})
export class TaskComponent {
    public day: number;
    constructor(private route: ActivatedRoute) {
        this.route.params.subscribe((params) => {
            if (params.day && parseInt(params.day, 10)) {
                this.day = parseInt(params.day, 10);
            }
        });
    }
}
