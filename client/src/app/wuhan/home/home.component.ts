import { Component, OnInit } from '@angular/core';

import { environment } from "./../../../environments/environment";
import { FormGroup, FormControl, Validators } from "@angular/forms";
import { HttpClient, HttpHeaders, HttpErrorResponse } from "@angular/common/http";
import { ClrLoadingState } from "@clr/angular";

interface algorithmListResponse {
	ok: boolean;
	algorithms: any;
}
interface lengthListResponse {
	ok: boolean;
	lengths: any;
}

@Component({
	selector: 'app-wuhan-home',
	templateUrl: './home.component.html',
	styleUrls: ['./home.component.styl']
})

export class WuhanHomeComponent implements OnInit {

	constructor(private http: HttpClient) { }

	public ok: boolean;
	public env: any = environment;

	ngOnInit() {
	}

	loginForm = new FormGroup({
		email: new FormControl("", [Validators.required, Validators.email]),
		password: new FormControl("", [Validators.required])
	});

	algorithmActive() {
		this.http
			.get<algorithmListResponse>(
				`${this.env.apps.wuhan.api}generate/hash/algorithm/list.php`
			)
			.subscribe(
				data => {
					if (data.ok) {
						this.ok = data.ok;
					} else {
						this.ok = data.ok;
					}
				},
				(err: HttpErrorResponse) => {
					this.ok = false;
				}
			);
	}

	lengthActive() {
		this.http
			.get<algorithmListResponse>(
				`${this.env.apps.wuhan.api}generate/hash/length/list.php`
			)
			.subscribe(
				data => {
					if (data.ok) {
						this.ok = data.ok;
					} else {
						this.ok = data.ok;
					}
				},
				(err: HttpErrorResponse) => {
					this.ok = false;
				}
			);
	}

}
