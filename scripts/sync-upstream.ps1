param(
    [string]$Remote = "upstream",
    [string]$Branch = "main",
    [ValidateSet("ff-only", "merge", "rebase")]
    [string]$Strategy = "ff-only",
    [switch]$AllowDirty
)

$ErrorActionPreference = "Stop"

function Fail {
    param([string]$Message)
    Write-Host "ERROR: $Message" -ForegroundColor Red
    exit 1
}

# Ensure this is a git repository
$null = git rev-parse --is-inside-work-tree 2>$null
if ($LASTEXITCODE -ne 0) {
    Fail "This directory is not a git repository."
}

# Ensure upstream remote exists
$remoteExists = git remote | Where-Object { $_ -eq $Remote }
if (-not $remoteExists) {
    Fail "Remote '$Remote' not found. Add it first with: git remote add $Remote <repo-url>"
}

# Prevent accidental pulls on dirty working trees unless explicitly allowed
if (-not $AllowDirty) {
    $status = git status --porcelain
    if ($status) {
        Fail "Working tree is not clean. Commit or stash changes first, then retry."
    }
}

Write-Host "Fetching $Remote/$Branch ..." -ForegroundColor Cyan
git fetch $Remote $Branch
if ($LASTEXITCODE -ne 0) {
    Fail "Failed to fetch from $Remote/$Branch."
}

$counts = git rev-list --left-right --count HEAD..."$Remote/$Branch"
if ($LASTEXITCODE -ne 0) {
    Fail "Failed to compare HEAD with $Remote/$Branch."
}

$parts = ($counts -split "\s+") | Where-Object { $_ -ne "" }
$ahead = [int]$parts[0]
$behind = [int]$parts[1]

if ($behind -eq 0) {
    Write-Host "Already up to date with $Remote/$Branch." -ForegroundColor Green
    exit 0
}

if ($ahead -gt 0) {
    Write-Host "Local branch is ahead by $ahead commit(s)." -ForegroundColor Yellow
}

switch ($Strategy) {
    "ff-only" {
        Write-Host "Pulling from $Remote/$Branch with --ff-only ..." -ForegroundColor Cyan
        git pull --ff-only $Remote $Branch
        if ($LASTEXITCODE -ne 0) {
            Fail "Pull failed in ff-only mode. Use Strategy=merge or Strategy=rebase for diverged branches."
        }
    }
    "merge" {
        Write-Host "Merging $Remote/$Branch into current branch ..." -ForegroundColor Cyan
        git merge "$Remote/$Branch"
        if ($LASTEXITCODE -ne 0) {
            Fail "Merge failed. Resolve conflicts, then run git add <files> and git commit."
        }
    }
    "rebase" {
        Write-Host "Rebasing current branch onto $Remote/$Branch ..." -ForegroundColor Cyan
        git rebase "$Remote/$Branch"
        if ($LASTEXITCODE -ne 0) {
            Fail "Rebase failed. Resolve conflicts, then run git rebase --continue or git rebase --abort."
        }
    }
}

Write-Host "Sync completed successfully." -ForegroundColor Green
