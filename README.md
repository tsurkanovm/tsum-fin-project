My pet project on the Magento2. 
The main extension (Tsum_CashFlow) allows an accounting of home cash flow. 

1.0.0 version:
- added cf_item entity
- product uses as storage item (with a separated attribute set)
- Order used as outcome movements (added cf-items to quote_item)
- added income document
- added the registry and storage_agg tables

2.0.0 version:
- no use of core M2 tables
- added custom tables for storage cash flow data

2.1.0
- added Tsum_Digits module - interactive game by knockout

2.2.0
- added odocker support

3.0.0
- added DEG_CustomReports for reports
- fixed small bugs

4.0.0
- added Tsum_CashFlow_Import module
- now import cash flow data form the bank available
