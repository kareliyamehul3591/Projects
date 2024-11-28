import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ViewGenSalaryComponent } from './view-gen-salary.component';

describe('ViewGenSalaryComponent', () => {
  let component: ViewGenSalaryComponent;
  let fixture: ComponentFixture<ViewGenSalaryComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ViewGenSalaryComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ViewGenSalaryComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
