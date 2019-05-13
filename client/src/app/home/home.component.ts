import { Component, OnInit } from '@angular/core';
import { environment } from './../../environments/environment';
import { Utils } from './../utils';

@Component({
	selector: 'app-home',
	templateUrl: './home.component.html',
	styleUrls: ['./home.component.styl']
})
export class HomeComponent implements OnInit {

	constructor() { }

	public env: any = environment;
	public randomApp: any;

	ngOnInit() {
		this.randomApp = new Utils().pickRandomApp();
	}

}