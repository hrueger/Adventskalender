import { Component } from "@angular/core";
import { Task } from "../../_models/Task";
import { RemoteService } from "../../_services/remote.service";

@Component({
    selector: "app-tasks",
    templateUrl: "./tasks.component.html",
    styleUrls: ["./tasks.component.scss"],
})
export class TasksComponent {
    tasks: Task[] = [];
    tasksInRandomOrder: Task[] = [];
    constructor(private remoteService: RemoteService) { }
    public ngOnInit(): void {
        this.remoteService.get("tasks").subscribe((t: Task[]) => {
            this.tasks = t;
            // eslint-disable-next-line no-use-before-define
            this.tasksInRandomOrder = shuffle([...t]);
        });
    }
}

/* taken from https://stackoverflow.com/a/2450976/13485777 */
function shuffle(array) {
    let currentIndex = array.length; let temporaryValue; let
        randomIndex;

    // While there remain elements to shuffle...
    while (currentIndex !== 0) {
        // Pick a remaining element...
        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex -= 1;

        // And swap it with the current element.
        temporaryValue = array[currentIndex];
        array[currentIndex] = array[randomIndex];
        array[randomIndex] = temporaryValue;
    }

    return array;
}
