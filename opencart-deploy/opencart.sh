git clone https://github.com/opencart/opencart.git
cd /home/ubuntu/workspace/opencart/
git checkout 2.1.0.2
mv /home/ubuntu/workspace/opencart/upload/* /home/ubuntu/workspace/
rm -rf /home/ubuntu/workspace/opencart
mv /home/ubuntu/workspace/config-dist.php /home/ubuntu/workspace/config.php 
mv /home/ubuntu/workspace/admin/config-dist.php /home/ubuntu/workspace/admin/config.php 