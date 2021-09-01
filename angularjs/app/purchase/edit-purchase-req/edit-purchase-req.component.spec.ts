import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EditPurchaseReqComponent } from './edit-purchase-req.component';

describe('EditPurchaseReqComponent', () => {
  let component: EditPurchaseReqComponent;
  let fixture: ComponentFixture<EditPurchaseReqComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EditPurchaseReqComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EditPurchaseReqComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
