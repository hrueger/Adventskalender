import { Component, OnInit } from "@angular/core";
import { RemoteService } from "../../_services/remote.service";
import { AlertService } from "../../_services/alert.service";
import { User } from "../../_models/User";

@Component({
    selector: "app-users",
    templateUrl: "./users.component.html",
    styleUrls: ["./users.component.scss"],
})
export class UsersComponent implements OnInit {
    public users: User[] = [];
    public userCount = 0;
    public currentUser: User;
    public order: "date" | "grade" = "grade";
    constructor(
        private remoteService: RemoteService,
        private alertService: AlertService,
    ) { }

    public ngOnInit(updateCurrentUser?: boolean): void {
        this.remoteService.get("users/admin").subscribe((data: User[]) => {
            if (data) {
                this.users = this.sortUsers(data);
                this.userCount = data.length;
                for (const user of this.users) {
                    if (updateCurrentUser && user.id == this.currentUser.id) {
                        this.currentUser = user;
                    }
                }
            }
        });
    }

    private sortUsers(data: User[]): User[] {
        return data.sort((a, b) => (this.order == "date"
            ? a.createdAt.toString().localeCompare(b.createdAt.toString())
            : a.grade.localeCompare(b.grade)));
    }

    public sort(type: "grade" | "date"): void {
        this.order = type;
        this.users = this.sortUsers(this.users);
    }

    public changeAdminStatus(user: User, willBeAdmin: boolean): void {
        // eslint-disable-next-line
        if (confirm(willBeAdmin ? `Soll" ${user.realname}" mit dem Nicknamen "${user.nickname}" wirklich zum Administrator gemacht werden?` : `Soll "${user.realname}" mit dem Nicknamen "${user.nickname}" wirklich kein Administrator mehr sein?`)) {
            this.remoteService.post(`users/${user.id}/admin`, { admin: willBeAdmin }).subscribe((data) => {
                if (data && data.success) {
                    this.alertService.success(willBeAdmin ? "Benutzer erfolgreich zum Admin gemacht!" : "Adminstatus erfolgreich entfernt!");
                    this.ngOnInit();
                }
            });
        }
    }

    public deleteUser(user: User): void {
        // eslint-disable-next-line
        if (confirm(`Soll" ${user.realname}" mit dem Nicknamen "${user.nickname}" wirklich gelöscht werden?`)) {
            this.remoteService.delete(`users/${user.id}`).subscribe((data) => {
                if (data && data.success) {
                    this.alertService.success("Benutzer erfolgreich gelöscht!");
                    this.ngOnInit();
                }
            });
        }
    }
}
