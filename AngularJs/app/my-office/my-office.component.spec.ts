import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MyOfficeComponent } from './my-office.component';

describe('MyOfficeComponent', () => {
  let component: MyOfficeComponent;
  let fixture: ComponentFixture<MyOfficeComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MyOfficeComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MyOfficeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
