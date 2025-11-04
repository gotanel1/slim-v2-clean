# GitHub Workflow Guide

คู่มือการใช้งาน Git และ GitHub สำหรับนักพัฒนา - ครอบคลุมตั้งแต่พื้นฐานจนถึงการทำงานร่วมกับทีม

---

## สารบัญ
1. [การเริ่มต้นใช้งาน Git](#การเริ่มต้นใช้งาน-git-กับ-repository)
2. [การอัพโค้ดขึ้น GitHub](#การอัพโค้ดขึ้น-github)
3. [Branch Management](#branch-management)
4. [การทำงานร่วมกับทีม](#การทำงานร่วมกับทีม)
5. [คำสั่งที่ใช้บ่อย](#คำสั่งที่ใช้บ่อย)
6. [Best Practices](#best-practices)
7. [Troubleshooting](#troubleshooting)

---

## การเริ่มต้นใช้งาน Git กับ Repository

### ตั้งค่า Git ครั้งแรก
```bash
# ตั้งค่าชื่อและอีเมล
git config --global user.name "Your Name"
git config --global user.email "your.email@example.com"

# ตรวจสอบการตั้งค่า
git config --list
```

### สร้าง Repository ใหม่
```bash
# เริ่มต้น Git ในโปรเจค
git init

# เพิ่ม Remote Repository
git remote add origin https://github.com/username/repository.git

# ตรวจสอบ Remote
git remote -v
```

### Clone Repository ที่มีอยู่แล้ว
```bash
# Clone ด้วย HTTPS
git clone https://github.com/username/repository.git

# Clone ด้วย SSH
git clone git@github.com:username/repository.git

# Clone และเปลี่ยนชื่อโฟลเดอร์
git clone https://github.com/username/repository.git my-project
```

---

## การอัพโค้ดขึ้น GitHub

### 1. เตรียมความพร้อม
```bash
# ตรวจสอบสถานะไฟล์ที่เปลี่ยนแปลง
git status

# ดูความแตกต่างของไฟล์
git diff
```

### 2. เพิ่มไฟล์เข้า Staging Area
```bash
# เพิ่มไฟล์ทั้งหมด
git add .

# หรือเพิ่มไฟล์เฉพาะ
git add <filename>

# เพิ่มหลายไฟล์
git add file1.php file2.js file3.css
```

### 3. Commit การเปลี่ยนแปลง
```bash
# Commit พร้อมข้อความ
git commit -m "ข้อความอธิบายการเปลี่ยนแปลง"

# ตัวอย่าง
git commit -m "Add new feature: user authentication"
git commit -m "Fix bug: login validation"
git commit -m "Update: improve performance"
```

### 4. Push ขึ้น GitHub
```bash
# Push ไปยัง branch ปัจจุบัน
git push

# Push ไปยัง branch เฉพาะ
git push origin main
git push origin develop

# Push ครั้งแรก (set upstream)
git push -u origin main
```

## Flow การทำงานแบบสมบูรณ์

```
[ทำงานกับโค้ด] 
    ↓
[git status] - ตรวจสอบไฟล์ที่เปลี่ยน
    ↓
[git add .] - เพิ่มไฟล์เข้า staging
    ↓
[git commit -m "message"] - บันทึกการเปลี่ยนแปลง
    ↓
[git push] - อัพโค้ดขึ้น GitHub
    ↓
[เสร็จสิ้น]
```

## Branch Management

### สร้าง Branch ใหม่
```bash
# สร้างและเปลี่ยนไปยัง branch ใหม่
git checkout -b feature/new-feature

# หรือใช้คำสั่งใหม่
git switch -c feature/new-feature
```

### เปลี่ยน Branch
```bash
# เปลี่ยนไปยัง branch อื่น
git checkout main
git checkout develop

# หรือใช้คำสั่งใหม่
git switch main
```

### Merge Branch
```bash
# เปลี่ยนไปยัง branch ปลายทาง
git checkout main

# Merge branch ที่ต้องการ
git merge feature/new-feature

# Push หลัง merge
git push
```

## การทำงานร่วมกับทีม

### 1. Pull การเปลี่ยนแปลงล่าสุด
```bash
# Pull จาก remote repository
git pull

# Pull จาก branch เฉพาะ
git pull origin main
```

### 2. Fetch และ Merge แยกส่วน
```bash
# Fetch ข้อมูลล่าสุด
git fetch origin

# ดู branch ทั้งหมด
git branch -a

# Merge การเปลี่ยนแปลง
git merge origin/main
```

### 3. จัดการ Conflicts
```bash
# เมื่อเกิด conflict
# 1. แก้ไขไฟล์ที่ conflict
# 2. เพิ่มไฟล์ที่แก้แล้ว
git add <resolved-file>

# 3. Commit
git commit -m "Resolve merge conflict"

# 4. Push
git push
```

## คำสั่งที่ใช้บ่อย

### ตรวจสอบสถานะ
```bash
# ดูสถานะ
git status

# ดู log
git log
git log --oneline
git log --graph --oneline --all

# ดู branch
git branch
git branch -a
```

### ย้อนกลับการเปลี่ยนแปลง
```bash
# ย้อนกลับไฟล์ที่ยังไม่ add
git checkout -- <filename>

# ย้อนกลับไฟล์ที่ add แล้ว
git reset HEAD <filename>

# ย้อนกลับ commit ล่าสุด (เก็บการเปลี่ยนแปลง)
git reset --soft HEAD~1

# ย้อนกลับ commit ล่าสุด (ลบการเปลี่ยนแปลง)
git reset --hard HEAD~1
```

### การตั้งค่า
```bash
# ตั้งค่าชื่อและอีเมล
git config --global user.name "Your Name"
git config --global user.email "your.email@example.com"

# ตั้งค่าเฉพาะ Repository
git config user.name "Your Name"
git config user.email "your.email@example.com"

# ดูการตั้งค่า
git config --list

# ตั้งค่า Default Branch
git config --global init.defaultBranch main

# ตั้งค่า Editor
git config --global core.editor "code --wait"  # VS Code
```

### การจัดการ Remote
```bash
# ดู Remote ทั้งหมด
git remote -v

# เพิ่ม Remote
git remote add origin https://github.com/username/repo.git

# เปลี่ยน URL ของ Remote
git remote set-url origin https://github.com/username/new-repo.git

# ลบ Remote
git remote remove origin

# เปลี่ยนชื่อ Remote
git remote rename origin upstream
```

## Best Practices

### 1. Commit Message ที่ดี
- ใช้ Present tense: "Add feature" ไม่ใช่ "Added feature"
- ข้อความสั้นกระชับ ชัดเจน
- เริ่มด้วยตัวพิมพ์ใหญ่
- ไม่ใส่จุดท้ายประโยค

**ตัวอย่าง:**
```
Add user login functionality
Fix navigation bar responsive issue
Update README documentation
Remove unused dependencies
```

### 2. Commit บ่อยๆ
- Commit เมื่อทำงานเสร็จในแต่ละส่วนเล็กๆ
- อย่ารอให้เปลี่ยนแปลงมากเกินไป
- แต่ละ commit ควรมีความหมายและทำงานได้

### 3. Pull ก่อน Push
```bash
# ดึงการเปลี่ยนแปลงล่าสุดก่อนเสมอ
git pull
git add .
git commit -m "Your message"
git push
```

### 4. ใช้ .gitignore
สร้างไฟล์ `.gitignore` เพื่อไม่ให้ track ไฟล์ที่ไม่จำเป็น
```
node_modules/
vendor/
.env
.DS_Store
*.log
storage/logs/
```

## Flow สำหรับ Feature ใหม่

```bash
# 1. Pull ล่าสุด
git pull origin main

# 2. สร้าง branch ใหม่
git checkout -b feature/new-feature

# 3. ทำงานและ commit
git add .
git commit -m "Add new feature"

# 4. Push branch ใหม่
git push -u origin feature/new-feature

# 5. สร้าง Pull Request บน GitHub

# 6. หลัง merge แล้วกลับไป main และ pull
git checkout main
git pull origin main

# 7. ลบ branch เก่า (ถ้าต้องการ)
git branch -d feature/new-feature
git push origin --delete feature/new-feature
```

## การใช้ Tags

### สร้างและจัดการ Tags
```bash
# สร้าง Lightweight Tag
git tag v1.0.0

# สร้าง Annotated Tag (แนะนำ)
git tag -a v1.0.0 -m "Release version 1.0.0"

# ดู Tags ทั้งหมด
git tag
git tag -l "v1.*"

# ดูรายละเอียด Tag
git show v1.0.0

# Push Tag ขึ้น GitHub
git push origin v1.0.0

# Push Tags ทั้งหมด
git push origin --tags

# ลบ Tag (Local)
git tag -d v1.0.0

# ลบ Tag (Remote)
git push origin --delete v1.0.0

# Checkout ไปยัง Tag
git checkout v1.0.0
```

## การใช้ Stash

### บันทึกงานชั่วคระ
```bash
# Stash การเปลี่ยนแปลงปัจจุบัน
git stash

# Stash พร้อมข้อความ
git stash save "Work in progress: feature X"

# Stash รวมถึงไฟล์ที่ยังไม่ได้ track
git stash -u

# ดูรายการ Stash
git stash list

# ดูเนื้อหาใน Stash
git stash show
git stash show -p stash@{0}

# Apply Stash ล่าสุด (เก็บ stash ไว้)
git stash apply

# Apply Stash เฉพาะ
git stash apply stash@{1}

# Pop Stash ล่าสุด (ลบ stash ออก)
git stash pop

# ลบ Stash
git stash drop stash@{0}

# ลบ Stash ทั้งหมด
git stash clear
```

## การดู History และ Diff

### ตรวจสอบประวัติ
```bash
# ดู Log แบบต่างๆ
git log
git log --oneline
git log --graph --oneline --all
git log --graph --decorate --oneline

# ดู Log ของไฟล์เฉพาะ
git log --follow -- filename.txt

# ดู Log ของ Author
git log --author="John"

# ดู Log ตามวันที่
git log --since="2024-01-01"
git log --until="2024-12-31"
git log --since="2 weeks ago"

# ดู Log พร้อม Diff
git log -p
git log -p -2  # 2 commits ล่าสุด

# ดูการเปลี่ยนแปลง
git diff                          # Unstaged changes
git diff --staged                 # Staged changes
git diff HEAD                     # All changes
git diff branch1..branch2         # ระหว่าง 2 branches
git diff commit1 commit2          # ระหว่าง 2 commits
git diff --stat                   # Summary

# ดูว่าใครแก้แต่ละบรรทัด
git blame filename.txt
git blame -L 10,20 filename.txt   # บรรทัด 10-20
```

## Troubleshooting

### ลืม Pull ก่อน Push
```bash
git pull --rebase origin main
git push
```

### Push ไม่ได้ เพราะมีการเปลี่ยนแปลงบน Remote
```bash
# Stash การเปลี่ยนแปลงปัจจุบัน
git stash

# Pull การเปลี่ยนแปลง
git pull

# Apply stash กลับมา
git stash pop

# แก้ conflict (ถ้ามี) แล้ว push
git push
```

### Commit ผิด ต้องการแก้ไข
```bash
# แก้ commit message ล่าสุด
git commit --amend -m "New message"

# เพิ่มไฟล์เข้า commit ล่าสุด
git add forgotten-file.txt
git commit --amend --no-edit

# ย้อนกลับ commit ล่าสุด (เก็บการเปลี่ยนแปลง)
git reset --soft HEAD~1

# ย้อนกลับ commit ล่าสุด (ลบการเปลี่ยนแปลง)
git reset --hard HEAD~1
```

### แก้ไข Commit Message เก่า
```bash
# Rebase เพื่อแก้ไข commits ย้อนหลัง
git rebase -i HEAD~3  # 3 commits ล่าสุด

# เปลี่ยน 'pick' เป็น 'reword' หรือ 'edit'
# บันทึกและทำตามคำแนะนำ
```

### ลบไฟล์ที่ commit ไปแล้ว
```bash
# ลบไฟล์จาก Git แต่เก็บไว้ในเครื่อง
git rm --cached filename.txt
git commit -m "Remove file from Git tracking"

# ลบไฟล์โฟลเดอร์
git rm --cached -r foldername/
```

### Branch ผิดต้องการย้าย Commit
```bash
# บันทึก commit hash ที่ต้องการย้าย
git log --oneline

# ย้ายไป branch ที่ถูกต้อง
git checkout correct-branch

# Cherry-pick commit ที่ต้องการ
git cherry-pick <commit-hash>

# กลับไปลบ commit ที่ branch เดิม
git checkout wrong-branch
git reset --hard HEAD~1
```

### Reset Remote Repository
```bash
# ระวัง: คำสั่งนี้จะลบประวัติบน Remote
git push origin main --force

# ปลอดภัยกว่า: ใช้ force-with-lease
git push origin main --force-with-lease
```

---

## Quick Reference

| คำสั่ง | คำอธิบาย |
|--------|----------|
| `git status` | ดูสถานะไฟล์ |
| `git add .` | เพิ่มไฟล์ทั้งหมด |
| `git commit -m "msg"` | Commit พร้อมข้อความ |
| `git push` | Push ขึ้น GitHub |
| `git pull` | ดึงการเปลี่ยนแปลงล่าสุด |
| `git clone <url>` | Clone repository |
| `git branch` | ดู branch ทั้งหมด |
| `git checkout -b <name>` | สร้าง branch ใหม่ |
| `git merge <branch>` | Merge branch |
| `git log` | ดู commit history |

## การเริ่มต้นใช้งาน Git กับ Repository

### ตั้งค่า Git ครั้งแรก
```bash
# ตั้งค่าชื่อและอีเมล
git config --global user.name "Your Name"
git config --global user.email "your.email@example.com"

# ตรวจสอบการตั้งค่า
git config --list
```

### สร้าง Repository ใหม่
```bash
# เริ่มต้น Git ในโปรเจค
git init

# เพิ่ม Remote Repository
git remote add origin https://github.com/username/repository.git

# ตรวจสอบ Remote
git remote -v
```

### Clone Repository ที่มีอยู่แล้ว
```bash
# Clone ด้วย HTTPS
git clone https://github.com/username/repository.git

# Clone ด้วย SSH
git clone git@github.com:username/repository.git

# Clone และเปลี่ยนชื่อโฟลเดอร์
git clone https://github.com/username/repository.git my-project
```

---

**อัพเดทล่าสุด:** 2025-11-04
