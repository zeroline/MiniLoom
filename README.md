[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/) ![Build and test workflow](https://github.com/zeroline/MiniLoom/actions/workflows/php.yml/badge.svg) [![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=zeroline_MiniLoom&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=zeroline_MiniLoom)

# MiniLoom

MiniLoom is a small PHP library I originally started years ago while learning and experimenting with PHP.

It’s not a framework and doesn’t try to be one. Instead, it’s a collection of pieces that grew over time while I explored different concepts like database access, simple ORM patterns, routing, HTTP handling, JWT, and CLI tooling.

The main goal was (and still is) to have something I understand end-to-end and can use to prototype ideas quickly without pulling in a full stack of dependencies.

# Why it exists

This project started as a way for me to learn PHP beyond just using existing frameworks. Instead of treating things like ORM, routing, or HTTP as black boxes, I wanted to understand how they actually work.

Over time, MiniLoom became my personal toolbox — something I can use to spin up prototypes quickly, test ideas, or just build small projects without overengineering them.

But remember! MiniLoom does not want or wanted to replace the astonishing PHP frameworks out there. It was built to be a solution for specific tasks in a specific way. Neither does it feature a full production ready framework experience nor does it cover current state of the art concepts.

# What it includes

MiniLoom bundles a few practical building blocks:

- basic database access and abstraction
- a lightweight ORM-style approach (nothing fancy, just enough to work with data comfortably)
- simple routing for HTTP-based projects
- helpers for working with requests and responses
- JWT handling for authentication experiments
- some CLI utilities for running scripts and small tools
- general helpers that accumulated over time

# What it includes

MiniLoom is:

- simple
- opinionated in places
- easy to modify if you know PHP

MiniLoom is not:

- a full-featured framework
- production-hardened in every corner
- trying to compete with established ecosystems
