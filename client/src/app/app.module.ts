import { BrowserModule, Title } from "@angular/platform-browser";
import { NgModule } from "@angular/core";
import { ReactiveFormsModule } from "@angular/forms";
import { HttpClientModule } from "@angular/common/http";

import { AppRoutingModule } from "./app-routing.module";
import { AppComponent } from "./app.component";
import { ClarityModule } from "@clr/angular";
import { BrowserAnimationsModule } from "@angular/platform-browser/animations";
import { HomeComponent } from './home/home.component';
import { PageNotFoundComponent } from './page-not-found/page-not-found.component';
import { ParisHomeComponent } from './paris/home/home.component';
import { WuhanHomeComponent } from './wuhan/home/home.component';

@NgModule({
	declarations: [
		AppComponent,
		HomeComponent,
		PageNotFoundComponent,
		ParisHomeComponent,
		WuhanHomeComponent,
	],
	imports: [
		BrowserModule,
		ReactiveFormsModule,
		HttpClientModule,
		AppRoutingModule,
		ClarityModule,
		BrowserAnimationsModule
	],
	providers: [Title],
	bootstrap: [AppComponent]
})
export class AppModule { }