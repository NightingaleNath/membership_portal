<?php

// Fetch Currency from Settings
$settingsQuery = "SELECT currency FROM settings LIMIT 1";
$settingsResult = mysqli_query($conn, $settingsQuery);
$currency = mysqli_fetch_assoc($settingsResult)['currency'];

// Count Total Members
$totalMembersQuery = "SELECT COUNT(*) as total_members FROM members";
$totalMembersResult = mysqli_query($conn, $totalMembersQuery);
$totalMembers = mysqli_fetch_assoc($totalMembersResult)['total_members'];

// Count Membership Types
$membershipTypesQuery = "SELECT COUNT(*) as membership_types FROM membership_types";
$membershipTypesResult = mysqli_query($conn, $membershipTypesQuery);
$membershipTypes = mysqli_fetch_assoc($membershipTypesResult)['membership_types'];

// Count Expired Memberships
$expiredMembershipQuery = "SELECT COUNT(*) as expired_members FROM members WHERE expiry_date < CURDATE()";
$expiredMembershipResult = mysqli_query($conn, $expiredMembershipQuery);
$expiredMembers = mysqli_fetch_assoc($expiredMembershipResult)['expired_members'];

// Calculate Total Revenue (assuming a `renew` table has a `amount` column for revenue)
$totalRevenueQuery = "SELECT SUM(total_amount) as total_revenue FROM renew";
$totalRevenueResult = mysqli_query($conn, $totalRevenueQuery);
$totalRevenue = mysqli_fetch_assoc($totalRevenueResult)['total_revenue'];

// Append Currency to Total Revenue
$formattedRevenue = $currency . ' ' . number_format($totalRevenue, 2);
?>
