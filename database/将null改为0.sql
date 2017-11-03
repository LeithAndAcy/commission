USE [commission]
update commission_customer_funds set this_month_funds_back = 0;
update commission_customer_funds set this_month_funds = 0;
update commission_customer_funds set last_month_benefit = 0;
update commission_customer_funds set this_month_settled_money = 0;

