use UFDATA_111_2015;
select DispatchList.cPersonCode as salesman_id,DispatchList.cCusCode as customer_id,
		Person.cPersonName as salesman_name,Customer.cCusName as customer_name,
		DispatchList.cDefine2 as contact_id,DispatchList.cSOCode,
		DispatchLists.cInvCode as inventory_id,DispatchLists.iQuantity as delivery_quantity,
		Inventory.cInvName as inventory_name,Inventory.cInvStd as specification,
		DispatchLists.cFree1 as colour,DispatchLists.iQuantity as delivery_quantity
		from DispatchList 
		join DispatchLists on DispatchList.cSOCode = DispatchLists.cSOCode and DispatchList.DLID  = DispatchLists.DLID
		join Inventory on DispatchLists.cInvCode = Inventory.cInvCode and Inventory.cInvDefine1  = DispatchLists.cFree1
		left join Person on Person.cPersonCode = DispatchList.cPersonCode
		left join Customer on Customer.cCusCode = DispatchList.cCusCode
		where DispatchList.dverifydate between '2014-01-01' and '2014-12-30'