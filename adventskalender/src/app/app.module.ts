import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { NavbarComponent } from './_components/navbar/navbar.component';
import { FooterComponent } from './_components/footer/footer.component';
import { LoginComponent } from './_pages/login/login.component';
import { RegisterComponent } from './_pages/register/register.component';
import { ScoresComponent } from './_pages/scores/scores.component';
import { RulesComponent } from './_pages/rules/rules.component';
import { HomeComponent } from './_pages/home/home.component';

@NgModule({
  declarations: [
    AppComponent,
    NavbarComponent,
    FooterComponent,
    LoginComponent,
    RegisterComponent,
    ScoresComponent,
    RulesComponent,
    HomeComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
