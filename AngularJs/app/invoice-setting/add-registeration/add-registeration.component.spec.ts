import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AddRegisterationComponent } from './add-registeration.component';

describe('AddRegisterationComponent', () => {
  let component: AddRegisterationComponent;
  let fixture: ComponentFixture<AddRegisterationComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AddRegisterationComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AddRegisterationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
