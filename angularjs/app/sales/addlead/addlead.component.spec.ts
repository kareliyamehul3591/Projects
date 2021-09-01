import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AddleadComponent } from './addlead.component';

describe('AddleadComponent', () => {
  let component: AddleadComponent;
  let fixture: ComponentFixture<AddleadComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AddleadComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AddleadComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
