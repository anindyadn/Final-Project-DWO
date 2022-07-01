<%@ page session="true" contentType="text/html; charset=ISO-8859-1" %>
<%@ taglib uri="http://www.tonbeller.com/jpivot" prefix="jp" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jstl/core" %>


<jp:mondrianQuery id="query01" jdbcDriver="com.mysql.jdbc.Driver" 
jdbcUrl="jdbc:mysql://localhost/aw_olap?user=root&password=" catalogUri="/WEB-INF/queries/aw_olap.xml">

select {[Measures].[Order Quantity],[Measures].[Total Price]} ON COLUMNS,
  {([Employee].[All Employees],[Store].[All Stores],[Customer].[All Customers],[Product].[All Products],[Date Dimens].[All Times])} ON ROWS
from [Sales]


</jp:mondrianQuery>





<c:set var="title01" scope="session">Query ADVENTURE WORKS using Mondrian OLAP</c:set>
