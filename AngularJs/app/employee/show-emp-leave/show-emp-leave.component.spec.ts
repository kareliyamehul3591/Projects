import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ShowEmpLeaveComponent } from './show-emp-leave.component';

describe('ShowEmpLeaveComponent', () => {
  let component: ShowEmpLeaveComponent;
  let fixture: ComponentFixture<ShowEmpLeaveComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ShowEmpLeaveComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ShowEmpLeaveComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
