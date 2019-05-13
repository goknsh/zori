import { environment } from "./../environments/environment";

export class Utils {
	constructor() { }

	public routerData: any;
	public apps: any = environment.apps;

	pickRandomApp() {
		let appsArr = [];
		Object.keys(this.apps).map((key) => {
			appsArr.push(key);
		});
		return this.apps[appsArr[Math.floor(Math.random() * appsArr.length)]];
	}
}