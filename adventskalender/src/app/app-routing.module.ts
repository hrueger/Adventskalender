import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { AuthenticationGuard } from "./_guards/authentication.guard";
import { HomeComponent } from "./_pages/home/home.component";
import { LoginComponent } from "./_pages/login/login.component";
import { RegisterComponent } from "./_pages/register/register.component";
import { RulesComponent } from "./_pages/rules/rules.component";
import { ScoresComponent } from "./_pages/scores/scores.component";
import { TaskComponent } from "./_pages/task/task.component";
import { TasksComponent } from "./_pages/tasks/tasks.component";
import { WelcomeComponent } from "./_pages/welcome/welcome.component";

const routes: Routes = [
    { path: "home", component: HomeComponent },
    { path: "welcome", component: WelcomeComponent, canActivate: [AuthenticationGuard] },
    { path: "tasks/:day", component: TaskComponent, canActivate: [AuthenticationGuard] },
    { path: "tasks", component: TasksComponent, canActivate: [AuthenticationGuard] },
    { path: "login", component: LoginComponent },
    { path: "register", component: RegisterComponent },
    { path: "scores", component: ScoresComponent },
    { path: "rules", component: RulesComponent },
    { path: "**", redirectTo: "welcome", pathMatch: "full" },
];

@NgModule({
    imports: [RouterModule.forRoot(routes)],
    exports: [RouterModule],
})
export class AppRoutingModule { }
