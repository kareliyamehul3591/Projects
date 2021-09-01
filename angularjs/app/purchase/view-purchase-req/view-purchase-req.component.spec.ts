import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ViewPurchaseReqComponent } from './view-purchase-req.component';

describe('ViewPurchaseReqComponent', () => {
  let component: ViewPurchaseReqComponent;
  let fixture: ComponentFixture<ViewPurchaseReqComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ViewPurchaseReqComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ViewPurchaseReqComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
