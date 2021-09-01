import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ShowSalaryComponent } from './show-salary.component';

describe('ShowSalaryComponent', () => {
  let component: ShowSalaryComponent;
  let fixture: ComponentFixture<ShowSalaryComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ShowSalaryComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ShowSalaryComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
