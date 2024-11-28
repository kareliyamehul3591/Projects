import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AddTermCondComponent } from './add-term-cond.component';

describe('AddTermCondComponent', () => {
  let component: AddTermCondComponent;
  let fixture: ComponentFixture<AddTermCondComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AddTermCondComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AddTermCondComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
