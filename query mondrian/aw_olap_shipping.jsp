<%@ page session="true" contentType="text/html; charset=ISO-8859-1" %>
<%@ taglib uri="http://www.tonbeller.com/jpivot" prefix="jp" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jstl/core" %>


<jp:mondrianQuery id="query01" jdbcDriver="com.mysql.jdbc.Driver" 
jdbcUrl="jdbc:mysql://localhost/aw_olap?user=root&password=" catalogUri="/WEB-INF/queries/aw_olap_shipping.xml">

select {[Measures].[Freight]} ON COLUMNS,
  {([Product].[All Products],[Sales Teritory].[All Sales Teritory],[Ship Method].[All Ship Method],[Date Dimens].[All Times])} ON ROWS
from [Shipping]


</jp:mondrianQuery>





<c:set var="title01" scope="session">Query ADVENTURE WORKS using Mondrian OLAP</c:set>
