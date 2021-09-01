import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CreateSalarySlipComponent } from './create-salary-slip.component';

describe('CreateSalarySlipComponent', () => {
  let component: CreateSalarySlipComponent;
  let fixture: ComponentFixture<CreateSalarySlipComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CreateSalarySlipComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CreateSalarySlipComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
