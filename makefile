FRONTEND=frontend
BACKEND=backend


# PHONY（避免 make 判斷錯誤）
.PHONY: up down restart logs \
        frontend frontend-install frontend-dev frontend-build frontend-lint \
        backend-install backend-key backend-migrate backend-seed backend-refresh backend-serve \
        init dev clean status rebuild health


# Docker 管理
up:
	docker-compose up -d --build

down:
	docker-compose down

restart:
	docker-compose down && docker-compose up -d --build

rebuild:
	docker-compose build --no-cache && docker-compose up -d

logs:
	docker-compose logs -f

status:
	docker-compose ps

health:
	@echo "Checking containers..."
	docker-compose ps
	@echo "Checking backend..."
	curl -s http://localhost:8000/api || echo "Backend not ready"


# 前端 Vue 3
frontend: frontend-dev

frontend-install:
	cd $(FRONTEND) && npm install

frontend-dev:
	cd $(FRONTEND) && npm run serve

frontend-build:
	cd $(FRONTEND) && npm run build

frontend-lint:
	cd $(FRONTEND) && npm run lint

frontend-reset:
	rm -rf $(FRONTEND)/node_modules $(FRONTEND)/dist
	cd $(FRONTEND) && npm install


# 後端 Laravel
backend-install:
	cd $(BACKEND) && composer install

backend-key:
	cd $(BACKEND) && php artisan key:generate

backend-migrate:
	cd $(BACKEND) && php artisan migrate

backend-seed:
	cd $(BACKEND) && php artisan db:seed

backend-refresh:
	cd $(BACKEND) && php artisan migrate:fresh --seed

backend-serve:
	cd $(BACKEND) && php artisan serve --host=0.0.0.0 --port=8000

backend-reset:
	rm -rf $(BACKEND)/vendor
	cd $(BACKEND) && composer install


# 初始化專案（第一次用）
init:
	@echo "Initializing EquipFlow..."
	make backend-install
	make backend-key
	make backend-migrate
	make frontend-install
	@echo "Init done!"


# 開發模式（非 Docker）
dev:
	@echo "Starting full-stack dev mode..."
	make backend-serve & make frontend-dev

# 一鍵重置整個專案
clean:
	@echo "Cleaning project..."
	rm -rf $(FRONTEND)/node_modules
	rm -rf $(FRONTEND)/dist
	rm -rf $(BACKEND)/vendor


# 完整重建（最常用）
fresh: clean init up
	@echo "Project fully rebuilt!"