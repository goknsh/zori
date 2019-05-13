import { Component, OnInit } from "@angular/core";
import { Router } from "@angular/router";

import { environment } from "./../../environments/environment";

@Component({
	selector: "app-page-not-found",
	templateUrl: "./page-not-found.component.html",
	styleUrls: ["./page-not-found.component.styl"]
})
export class PageNotFoundComponent implements OnInit {
	constructor(public router: Router) { }

	public environment = environment;

	ngOnInit() { }
}