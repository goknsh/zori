import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ParisHomeComponent } from './home.component';

describe('ParisHomeComponent', () => {
	let component: ParisHomeComponent;
	let fixture: ComponentFixture<ParisHomeComponent>;

	beforeEach(async(() => {
		TestBed.configureTestingModule({
			declarations: [ParisHomeComponent]
		})
			.compileComponents();
	}));

	beforeEach(() => {
		fixture = TestBed.createComponent(ParisHomeComponent);
		component = fixture.componentInstance;
		fixture.detectChanges();
	});

	it('should create', () => {
		expect(component).toBeTruthy();
	});
});
