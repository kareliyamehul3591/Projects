import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormSixteenComponent } from './form-sixteen.component';

describe('FormSixteenComponent', () => {
  let component: FormSixteenComponent;
  let fixture: ComponentFixture<FormSixteenComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormSixteenComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormSixteenComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
