import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ListPurchaseRequestComponent } from './list-purchase-request.component';

describe('ListPurchaseRequestComponent', () => {
  let component: ListPurchaseRequestComponent;
  let fixture: ComponentFixture<ListPurchaseRequestComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ListPurchaseRequestComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ListPurchaseRequestComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
