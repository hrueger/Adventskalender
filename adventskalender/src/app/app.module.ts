import { BrowserModule } from "@angular/platform-browser";
import { BrowserAnimationsModule } from "@angular/platform-browser/animations";
import { LOCALE_ID, NgModule } from "@angular/core";
import { HttpClientModule, HTTP_INTERCEPTORS } from "@angular/common/http";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { ToastrModule } from "ngx-toastr";
import { ServiceWorkerModule } from "@angular/service-worker";
import { registerLocaleData } from "@angular/common";
import localeDe from "@angular/common/locales/de";
import { AppRoutingModule } from "./app-routing.module";
import { AppComponent } from "./app.component";
import { NavbarComponent } from "./_components/navbar/navbar.component";
import { FooterComponent } from "./_components/footer/footer.component";
import { LoginComponent } from "./_pages/login/login.component";
import { RegisterComponent } from "./_pages/register/register.component";
import { ScoresComponent } from "./_pages/scores/scores.component";
import { RulesComponent } from "./_pages/rules/rules.component";
import { HomeComponent } from "./_pages/home/home.component";
import { ErrorInterceptor } from "./_interceptors/error.interceptor";
import { JwtInterceptor } from "./_interceptors/jwt.interceptor";
import { WelcomeComponent } from "./_pages/welcome/welcome.component";
import { TasksComponent } from "./_pages/tasks/tasks.component";
import { TaskComponent } from "./_pages/task/task.component";
import { UsersComponent } from "./_pages/users/users.component";
import { environment } from "../environments/environment";

registerLocaleData(localeDe);

@NgModule({
    declarations: [
        AppComponent,
        NavbarComponent,
        FooterComponent,
        LoginComponent,
        RegisterComponent,
        ScoresComponent,
        RulesComponent,
        HomeComponent,
        WelcomeComponent,
        TasksComponent,
        TaskComponent,
        UsersComponent,
    ],
    imports: [
        BrowserModule,
        AppRoutingModule,
        HttpClientModule,
        FormsModule,
        ReactiveFormsModule,
        ToastrModule.forRoot(),
        BrowserAnimationsModule,
        ServiceWorkerModule.register("ngsw-worker.js", { enabled: environment.production }),
    ],
    providers: [
        {
            provide: HTTP_INTERCEPTORS,
            useClass: ErrorInterceptor,
            multi: true,
        },
        {
            provide: HTTP_INTERCEPTORS,
            useClass: JwtInterceptor,
            multi: true,
        },
        {
            provide: LOCALE_ID,
            useValue: "de",
        },
    ],
    bootstrap: [AppComponent],
})
export class AppModule { }
