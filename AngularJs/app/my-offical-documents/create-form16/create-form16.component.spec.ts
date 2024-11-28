import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CreateForm16Component } from './create-form16.component';

describe('CreateForm16Component', () => {
  let component: CreateForm16Component;
  let fixture: ComponentFixture<CreateForm16Component>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CreateForm16Component ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CreateForm16Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
