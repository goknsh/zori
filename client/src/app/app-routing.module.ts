import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";

import { environment } from "./../environments/environment";

import { HomeComponent } from "./home/home.component";
import { PageNotFoundComponent } from './page-not-found/page-not-found.component'
import { ParisHomeComponent } from './paris/home/home.component';;
import { WuhanHomeComponent } from './wuhan/home/home.component';

const routes: Routes = [
	{
		path: environment.apps.wuhan.routerLink,
		component: WuhanHomeComponent,
		data: { title: environment.apps.wuhan.name + " // zori", styles: { padding: true } }
	},
	{
		path: environment.apps.paris.routerLink,
		component: ParisHomeComponent,
		data: { title: environment.apps.paris.name + " // zori", styles: { padding: true } }
	},

	// REDIRECTORS
	{
		path: "paris",
		redirectTo: environment.apps.paris.routerLink
	},
	{
		path: "wuhan",
		redirectTo: environment.apps.wuhan.routerLink,
	},

	{
		path: "",
		component: HomeComponent,
		data: { title: "home // zori", styles: { padding: true } }
	},
	{
		path: "**",
		component: PageNotFoundComponent,
		data: { title: "page not found // zori", styles: { padding: true } }
	}
];

@NgModule({
	imports: [RouterModule.forRoot(routes)],
	exports: [RouterModule]
})
export class AppRoutingModule { }