import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ShowEmpAttendComponent } from './show-emp-attend.component';

describe('ShowEmpAttendComponent', () => {
  let component: ShowEmpAttendComponent;
  let fixture: ComponentFixture<ShowEmpAttendComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ShowEmpAttendComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ShowEmpAttendComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
