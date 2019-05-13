import { Component, OnInit } from "@angular/core";
import { Router, NavigationEnd } from "@angular/router";
import { Title } from "@angular/platform-browser";

import { environment } from "./../environments/environment";

@Component({
	selector: "app-root",
	templateUrl: "./app.component.html",
	styleUrls: ["./app.component.styl"]
})
export class AppComponent implements OnInit {
	constructor(public router: Router, public titleService: Title) { }

	public routerData: any;
	public env: any = environment;

	ngOnInit() {
		this.router.events.subscribe((change: any) => {
			if (change instanceof NavigationEnd) {
				this.routerData = this.router.routerState.snapshot.root.firstChild.data;
				this.titleService.setTitle(this.routerData.title);
			}
		});
	}
}