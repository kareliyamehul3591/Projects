import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UpdategensalaryComponent } from './updategensalary.component';

describe('UpdategensalaryComponent', () => {
  let component: UpdategensalaryComponent;
  let fixture: ComponentFixture<UpdategensalaryComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UpdategensalaryComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UpdategensalaryComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
