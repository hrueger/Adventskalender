import { BrowserModule } from "@angular/platform-browser";
import { BrowserAnimationsModule } from "@angular/platform-browser/animations";
import { NgModule } from "@angular/core";
import { HttpClientModule, HTTP_INTERCEPTORS } from "@angular/common/http";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { ToastrModule } from "ngx-toastr";
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
    ],
    imports: [
        BrowserModule,
        AppRoutingModule,
        HttpClientModule,
        FormsModule,
        ReactiveFormsModule,
        ToastrModule.forRoot(),
        BrowserAnimationsModule,
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
    ],
    bootstrap: [AppComponent],
})
export class AppModule { }
