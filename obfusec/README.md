# Obfusec plugin for Craft CMS 3.x

Adminsitrative Authentication Obfuscation Security Related Plugin.

![Screenshot](resources/img/plugin-logo.png)

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require phendron/craft-obfusec

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Test Plugin.

## Obfusec Plugin Overview

Description: The Plugin allows admin control panel login request to be obfuscated through unique secure routes on a single or all user basis.

## Configuring Obfusec Plugin

##-Insert text here-

## Using Obfusec Plugin

-- Obfusec Settings Page --

** Admin Secure Login Path (Route): define the initial route path for all users e.g. example.com/admin/[secure_route]

** Default Global Path: defines the initial default route path after all users when {User Urls & Initialization}==off. e.g. example.com/admin/[secure_route]/[default_route]/

** Initial Users (Action Button): Generates Unique secure routess for all Uninitialized Users these secure routes are available in the sidebar menu as {Path Browser}.

** Re-Initialize All Users (Action Button): Re-Generates Unique secure routes for all Users also avail int he sidebar menu in {Path Browser}.

-- Obfusec Path Browser Page --

Description: The Path Browser page allows searching for usernames, emails & secure routes.

** Re-Generate Route (Action Button): Each Secure Path Entry has a button on the right side of the table that allows Re-Generation of Secure Routes to Issue new routes on per user basis.


## Obfusec Plugin Roadmap

Some things to do, and ideas for potential features:

* Release it

-- ISSUES --

* Add Admin Login Redirects to Dashboard.
* Add user notification of issued routes / new routes.
* Add Error handling on forms.
* Enhance Style.
Brought to you by [Patrick Hendron](https://github.com/phendron)
