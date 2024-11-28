import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EditattendComponent } from './editattend.component';

describe('EditattendComponent', () => {
  let component: EditattendComponent;
  let fixture: ComponentFixture<EditattendComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EditattendComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EditattendComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
