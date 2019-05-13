import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WuhanHomeComponent } from './home.component';

describe('WuhanHomeComponent', () => {
	let component: WuhanHomeComponent;
	let fixture: ComponentFixture<WuhanHomeComponent>;

	beforeEach(async(() => {
		TestBed.configureTestingModule({
			declarations: [WuhanHomeComponent]
		})
			.compileComponents();
	}));

	beforeEach(() => {
		fixture = TestBed.createComponent(WuhanHomeComponent);
		component = fixture.componentInstance;
		fixture.detectChanges();
	});

	it('should create', () => {
		expect(component).toBeTruthy();
	});
});
