import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EmpLeaveDetailsComponent } from './emp-leave-details.component';

describe('EmpLeaveDetailsComponent', () => {
  let component: EmpLeaveDetailsComponent;
  let fixture: ComponentFixture<EmpLeaveDetailsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EmpLeaveDetailsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EmpLeaveDetailsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
